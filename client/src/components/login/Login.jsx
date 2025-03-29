import { useActionState, useContext } from "react";
import { Link, useNavigate } from "react-router";

export default function Login() {

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Login</h1>
                    <section className="auth">
                        <form id="login" action="">
                            <div class="flex flex-col gap-2">
                                <label htmlFor="email">Email</label>
                                <input type="email" id="email" name="email" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label htmlFor="email">Password</label>
                                <input type="password" id="password" name="password" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <button type="submit">Login</button>
                            </div>
                        </form>
                    </section>
                </section>
            </div>
        </>
    );

}