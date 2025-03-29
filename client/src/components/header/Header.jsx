import { Link } from "react-router";

export default function Header() {
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

                <nav className="flex items-center gap-4">
                    <Link to="/login" className="text-3xl font-roboto-condensed text-black">Login</Link>
                    <Link to="/register" className="text-3xl font-roboto-condensed text-black">Register</Link>
                </nav>
            </div>
        </header>
    );
}