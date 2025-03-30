import { useContext } from "react";
import { UserContext } from "../contexts/UserContext";
import request from "../utils/request";

export default function useAuth() {
    const authData = useContext(UserContext);

    const requestWrapper = (method, url, data, options = {}) => {
        const authOptions = {
            ...options,
            headers: {
                'Authorization': `Bearer ${token}`,
                ...options.headers
            }
        };

        return request.apiRequest(url, data, authData.token ? authOptions : options);
    };

    return {
        ...authData,
        id: authData.id,
        isAuthenticated: !!authData.token,
        request
    }
};
