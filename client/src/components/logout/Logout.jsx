import { Navigate } from "react-router";
import { useLogout } from "../../stores/authStore";

export default function Logout() {
    const { isLoggedOut } = useLogout();

    console.log(isLoggedOut);

    return isLoggedOut
        ? <Navigate to="/" />
        : <Navigage to="/dashboard" />;
}
