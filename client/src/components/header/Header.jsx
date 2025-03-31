import { Link } from "react-router";
import useAuth from "../../hooks/useAuth";

export default function Header() {
    const { isAuthenticated } = useAuth();

    console.log(isAuthenticated);

    return (
        <header className="header-inner w-full">
            <div className="w-full py-4 flex items-center justify-evenly">
                <nav className="flex items-center gap-4">
                    <Link to="/" className="text-3xl font-roboto-condensed text-black">Home</Link>
                    <Link to="/recipes" className="text-3xl font-roboto-condensed text-black">Recipes</Link>
                </nav>
                
                <div className="flex items-center gap-2">
                    <img src="./public/images/logo.svg" className="h-[40px]" />
                    <span className="text-2xl font-roboto-mono text-black">Stay Healthy</span>
                </div>
                
                {(isAuthenticated === false || isAuthenticated === undefined) && (
                    <nav className="flex items-center gap-4">
                        <Link to="/login" className="text-3xl font-roboto-condensed text-black">Login</Link>
                        <Link to="/register" className="text-3xl font-roboto-condensed text-black">Register</Link>
                    </nav>
                )}

                {isAuthenticated === true && (
                    <nav className="flex items-center gap-4">
                        <Link to="/dashboard" className="text-3xl font-roboto-condensed text-black">Dashboard</Link>
                        <Link to="/my-recipes" className="text-3xl font-roboto-condensed text-black">My Recipes</Link>
                        <Link to="/logout" className="text-3xl font-roboto-condensed text-black">Logout</Link>
                    </nav>
                )}
                
            </div>
        </header>
    );
}