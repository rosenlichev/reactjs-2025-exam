import { useEffect, useContext } from "react";
import request from "../utils/request";

const baseUrl = "http://dev200.cobweb.work:82/reactjs-2025-exam/admin/wp-json/cws-tools/v1";

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