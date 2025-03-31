import request from "../utils/request";
import { useContext, useEffect, useState } from "react";
import { UserContext } from "../contexts/UserContext";

const baseUrl = "http://dev200.cobweb.work:82/reactjs-2025-exam/admin/wp-json/cws-tools/v1";

export const useRecipes = () => {
    const { token } = useContext(UserContext);
    const [recipes, setRecipes] = useState([]);

    useEffect(() => {
        if (token) {
            const headers = {
                'Authorization': `Bearer ${token}`,
            }

            request.apiRequestSimple(`${baseUrl}/getRecipes`, null, headers)
            .then(setRecipes);
        } else {
            request.apiRequestSimple(`${baseUrl}/getRecipes`)
            .then(setRecipes);
        }
        
    }, []);

    return { recipes };
}

export const useMyRecipes = () => {
    const { token } = useContext(UserContext);
    const [recipes, setRecipes] = useState([]);

    useEffect(() => {
        const headers = {
            'Authorization': `Bearer ${token}`,
        }

        request.apiRequestSimple(`${baseUrl}/getMyRecipes`, null, headers)
            .then(setRecipes);
    }, []);

    return { recipes };
}

// export const useAddComment = (data) => {
//     const { token } = useContext(UserContext);
//     const [recipe, setRecipe] = useState([]);

//     useEffect(() => {
//         const headers = {
//             'Authorization': `Bearer ${token}`,
//         }

//         request.apiRequestSimple(`${baseUrl}/getMyRecipes`, data, headers)
//             .then(setRecipe);
//     }, []);

//     return {
//         recipe
//     };
// }


export const  useAddComment = () => {
    const { token } = useContext(UserContext);

    const addComment = (data) => {
        const headers = {
            'Authorization': `Bearer ${token}`,
        }

        return request.apiRequest(`${baseUrl}/addComment`, data, headers);
    }

    return {
        addComment,
    };
}

export const useHomepageRecipes = () => {
    const [recipes, setRecipes] = useState([]);

    useEffect(() => {
        request.apiRequestSimple(`${baseUrl}/getRecipesHomepage`)
            .then(setRecipes);
    }, []);

    return { recipes };
}

export const useRecipe = (id) => {
    const [recipe, setRecipe] = useState([]);

    useEffect(() => {
        request.apiRequestSimple(`${baseUrl}/getRecipeDetails`, {id: id})
            .then(setRecipe);
    }, []);

    return { recipe };
}

export const useSetLiked = () => {
    const [recipe, setRecipe] = useState([]);
    const { token } = useContext(UserContext);

    const headers = {
        'Authorization': `Bearer ${token}`,
    };

    // Return a function that can be called from event handlers
    const setLikedFn = async (data) => {
        try {
            const result = await request.apiRequestSimple(`${baseUrl}/setLiked`, data, headers);
            setRecipe(result);
            return result;
        } catch (error) {
            console.error("Error setting liked status:", error);
            throw error;
        }
    };

    return setLikedFn;
};

export const useCreateRecipe = (data) => {

    const { token } = useContext(UserContext);

    const headers = {
        'Authorization': `Bearer ${token}`,
    }

    const createRecipe = (data) => {

        console.log(token);

        const headers = {
            'Authorization': `Bearer ${token}`,
        }
        return request.apiRequest(`${baseUrl}/saveOrUpdateRecipe`, data, headers);
    }

    return {
        createRecipe,
    };
}