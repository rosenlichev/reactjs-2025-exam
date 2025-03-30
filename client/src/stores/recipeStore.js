import request from "../utils/request";
import { useContext, useEffect, useState } from "react";
import { UserContext } from "../contexts/UserContext";

const baseUrl = "http://dev200.cobweb.work:82/reactjs-2025-exam/admin/wp-json/cws-tools/v1";

export const useRecipes = () => {
    const [recipes, setRecipes] = useState([]);

    useEffect(() => {
        request.apiRequestSimple(`${baseUrl}/getRecipes`)
            .then(setRecipes);
    }, []);

    return { recipes };
}

export const useMyRecipes = () => {
    const { token } = useContext(UserContext)
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