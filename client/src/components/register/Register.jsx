import parse from 'html-react-parser';
import { useState, useActionState, useContext } from "react";
import { Link, useNavigate } from "react-router";
import { useRegister } from "../../stores/authStore";
import { useUserContext } from "../../contexts/UserContext";

export default function Register() {
    const navigate = useNavigate();
    const {register} = useRegister();
    const {userLoginHandler} = useUserContext();
    const [message, setMessage] = useState("");
    const [isError, setIsError] = useState(false);
    const [formData, setFormData] = useState({
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        confirm_password: ""
    });

    const handleChange = (event) => {
        const { name, value } = event.target;

        setFormData((prevData) => ({
        ...prevData,
        [name]: value, // Update the specific field dynamically
        }));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        const responseData = await register(formData);

        if (responseData.status == 400) {
            const data = await responseData.json();

            setMessage(data.message);
            setIsError(true);
        } else {
            const data = await responseData.json();
            
            setMessage(data.message);
            setIsError(false);

            userLoginHandler(data);

            navigate('/dashboard');
        }
    }

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Register</h1>
                    <section className="auth">
                        <form id="login" onSubmit={handleSubmit}>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">First name</label>
                                <input type="text" id="first-name" name="first_name" onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">Last name</label>
                                <input type="text" id="last-name" name="last_name" onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">Email</label>
                                <input type="email" id="email" name="email" onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">Password</label>
                                <input type="password" id="password" name="password" onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="email">Confirm password</label>
                                <input type="password" id="confirm-password" name="confirm_password" onChange={handleChange} />
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
                                <button type="submit">Register</button>
                            </div>
                        </form>
                    </section>
                </section>
            </div>
        </>
    );

}