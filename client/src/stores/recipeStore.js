import { useEffect, useState } from "react";
import request from "../utils/request";

const baseUrl = "http://dev200.cobweb.work:82/reactjs-2025-exam/admin/wp-json/cws-tools/v1";

export const useRecipes = () => {
    const [recipes, setRecipes] = useState([]);

    useEffect(() => {
        request.apiRequestSimple(`${baseUrl}/getRecipes`)
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