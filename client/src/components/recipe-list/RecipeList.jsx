import { useRecipes } from "../../stores/recipeStore";
import RecipeListItem from "./recipe-list-item/RecipeListItem";

export default function RecipesList() {
    const {recipes} = useRecipes();

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">Recipes</h1>
                    <div className="recipes">
                        <div className="recipes-grid">
                            {recipes.length > 0
                                ? recipes.map(recipe => <RecipeListItem key={recipe.id} recipe={recipe} />)
                                : <p className="text-2xl text-center">No recipes found.</p>
                            }
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}