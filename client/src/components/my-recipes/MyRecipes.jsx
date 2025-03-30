import { useMyRecipes } from "../../stores/recipeStore";
import MyRecipesItem from "./my-recipes-item/MyRecipesItem";
import { Link } from "react-router-dom";

export default function MyRecipes() {
    const {recipes} = useMyRecipes();

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration relative mb-10 text-6xl font-roboto-mono-italic text-center text-black">
                        My Recipes

                        <Link to="/recipe-create" className="absolute right-0 bottom-6 p-2 text-sm text-white rounded" style={{backgroundColor: '#03835a'}}>Add recipe</Link>
                    </h1>
                    <div className="recipes">
                        <div className="recipes-grid">
                            {recipes.length > 0
                                ? recipes.map(recipe => <MyRecipesItem key={recipe.id} recipe={recipe} />)
                                : <p className="text-2xl text-center">You haven't published any recipes yet.</p>
                            }
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
}