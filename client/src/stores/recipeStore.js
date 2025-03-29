import { useEffect, useState } from "react";
import request from "../utils/request";

const baseUrl = "http://dev200.cobweb.work:82/reactjs-2025-exam/admin/wp-json/cws-tools/v1";

export const useRecipes = () => {
    const [recipes, setRecipes] = useState([]);

    useEffect(() => {
        request.apiRequest({}, {}, `${baseUrl}/getRecipes`)
            .then(setRecipes);
    }, []);

    return { recipes };
}

export const useRecipe = (id) => {
    const [recipe, setRecipe] = useState([]);

    useEffect(() => {
        request.apiRequest({}, {id: id}, `${baseUrl}/getRecipeDetails`)
            .then(setRecipe);
    }, []);

    return { recipe };
}