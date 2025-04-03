import { useEffect, useContext } from "react";
import request from "../utils/request";
import { UserContext } from "../contexts/UserContext";

const baseUrl = "https://reactjs-2025-exam.bglist.com/wp-json/cws-tools/v1";

export const useRegister = () => {
    const register = (data) => {
        return request.apiRequest(`${baseUrl}/registerUser`, data);
    }

    return {
        register,
    };
}

export const useLogin = () => {
    const login = (data) => {
        return request.apiRequest(`${baseUrl}/loginUser`, data);
    }

    return {
        login,
    };
}

export const useLogout = () => {
    const { token, userLogoutHandler } = useContext(UserContext);

    useEffect(() => {
        if (!token) {
            return;
        }

        const headers = {
            'Authorization': `Bearer ${token}`,
        }

        request.apiRequest(`${baseUrl}/logoutUser`, null, headers);

    }, [token, userLogoutHandler]);

    return {
        isLoggedOut: !!token,
    };
};