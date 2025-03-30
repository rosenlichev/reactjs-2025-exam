import { useContext } from "react";
import { UserContext } from "../../contexts/UserContext";

export default function Dashboard() {
    const {first_name, last_name} = useContext(UserContext);

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Dashboard</h1>
                    <div className="dashboard">
                          {first_name && last_name && (
                            <>
                                <h2 className="mb-4 text-4xl text-center">Greetings, {first_name} {last_name}</h2>
                                <h3 className="mb-4 text-2xl text-center">This page will be available later on :)</h3>
                            </>
                          )}
                    </div>
                </section>
            </div>
        </>
    );
}