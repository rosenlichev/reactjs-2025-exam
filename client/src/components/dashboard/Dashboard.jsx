import { UserContext } from "../../contexts/UserContext";

export default function Dashboard() {
    console.log(UserContext);
    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Dashboard</h1>
                    <div className="dashboard">
                          
                    </div>
                </section>
            </div>
        </>
    );
}