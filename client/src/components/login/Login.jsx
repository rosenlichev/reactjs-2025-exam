import parse from 'html-react-parser';
import { useState, useActionState, useContext } from "react";
import { Link, useNavigate } from "react-router";
import { useLogin } from "../../stores/authStore";
import { UserContext } from "../../contexts/UserContext";

export default function Login() {
    const navigate = useNavigate();
    const {login} = useLogin();
    const {userLoginHandler} = useContext(UserContext);
    const [message, setMessage] = useState("");
    const [isError, setIsError] = useState(false);
    const [formData, setFormData] = useState({
        email: "",
        password: "",
    });

    const handleChange = (event) => {
        const { name, value } = event.target;

        setFormData((prevData) => ({
        ...prevData,
        [name]: value, // Update the specific field dynamically
        }));
    };

    const handleSubmit = async (_, formData) => {
        // event.preventDefault();

        const values = Object.fromEntries(formData);

        const responseData = await login({email: values.email, password: values.password});

        if (responseData.status == 400) {
            const data = await responseData.json();

            setMessage(data.message);
            setIsError(true);
        } else {
            const data = await responseData.json();
            
            setMessage(data.message);
            setIsError(false);

            console.log(data);

            userLoginHandler(data);

            // navigate('/dashboard');
        }
    }

    const [_, loginAction, isPending] = useActionState(handleSubmit, { email: '', password: '' });

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Login</h1>
                    <section className="auth">
                        <form id="login" action={loginAction}>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">Username / Email</label>
                                <input type="email" id="email" name="email" onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">Password</label>
                                <input type="password" id="password" name="password" onChange={handleChange} />
                            </div>

                            {message !== '' && (
                                <>
                                    {isError === true && (
                                        <div className="p-4 rounded bg-red-300 text-red-900">
                                            {parse(message)}
                                        </div>
                                    )}
                                    {isError === false && (
                                        <div className="p-4 rounded bg-green-300 text-green-900">
                                            {parse(message)}
                                        </div>
                                    )}
                                </>
                            )}

                            <div className="flex flex-col gap-2">
                                <button type="submit">Login</button>
                            </div>
                        </form>
                    </section>
                </section>
            </div>
        </>
    );

}