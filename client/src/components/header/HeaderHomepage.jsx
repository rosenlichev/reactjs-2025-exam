import { Link } from "react-router";
import useAuth from "../../hooks/useAuth";

export default function HeaderHomepage() {
    const { isAuthenticated } = useAuth();

    return (
        <header className="header-homepage w-full absolute top-0 left-0 z-1">
            <div className="w-full py-4 flex items-center justify-evenly">
                <nav className="flex items-center gap-4">
                    <Link to="/" className="text-3xl font-roboto-condensed text-white">Home</Link>
                    <Link to="/recipes" className="text-3xl font-roboto-condensed text-white">Recipes</Link>
                </nav>
                
                <div className="flex items-center gap-2">
                    <img src="./public/images/logo.svg" className="h-[40px]" />
                    <span className="text-2xl font-roboto-mono text-white">Stay Healthy</span>
                </div>

                {isAuthenticated === false && (
                    <nav className="flex items-center gap-4">
                        <Link to="/login" className="text-3xl font-roboto-condensed text-white">Login</Link>
                        <Link to="/register" className="text-3xl font-roboto-condensed text-white">Register</Link>
                    </nav>
                )}

                {isAuthenticated === true && (
                    <nav className="flex items-center gap-4">
                        <Link to="/dashboard" className="text-3xl font-roboto-condensed text-white">Dashboard</Link>
                        <Link to="/my-recipes" className="text-3xl font-roboto-condensed text-white">My Recipes</Link>
                        <Link to="/logout" className="text-3xl font-roboto-condensed text-white">Logout</Link>
                    </nav>
                )}
            </div>
        </header>
    );
}