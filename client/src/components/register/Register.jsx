import { useActionState, useContext } from "react";
import { Link, useNavigate } from "react-router";

export default function Register() {

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Register</h1>
                    <section className="auth">
                        <form id="login" action="">
                        <div class="flex flex-col gap-2">
                                <label htmlFor="email">First name</label>
                                <input type="text" id="first-name" name="first_name" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label htmlFor="email">Last name</label>
                                <input type="text" id="last-name" name="last_name" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label htmlFor="email">Email</label>
                                <input type="email" id="email" name="email" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label htmlFor="email">Password</label>
                                <input type="password" id="password" name="password" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <label htmlFor="email">Confirm password</label>
                                <input type="password" id="confirm-password" name="confirm_password" />
                            </div>
                            <div class="flex flex-col gap-2">
                                <button type="submit">Register</button>
                            </div>
                        </form>
                    </section>
                </section>
            </div>
        </>
    );

}