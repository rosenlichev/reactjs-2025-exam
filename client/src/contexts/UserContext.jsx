import { createContext, useContext } from "react";

export const UserContext = createContext({
    id: '',
    token: '',
    email: '',
    display_name: '',
    first_name: '',
    last_name: '',
    userLoginHandler: () => null,
    userLogoutHandler: () => null,
});

export function useUserContext() {
    const data = useContext(UserContext);

    return data;
}
