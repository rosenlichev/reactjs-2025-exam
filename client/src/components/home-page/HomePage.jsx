import { useContext } from "react";
import { useHomepageRecipes } from "../../stores/recipeStore";
import { UserContext } from "../../contexts/UserContext";
import Banner from "./banner/Banner";
import HomepageListItem from "./homepage-list-item/HomepageListItem";

export default function HomePage() {
    const {recipes} = useHomepageRecipes();
    
    return (
        <>
            <div className="page-wrapper home">
                <Banner />

                {recipes.length > 0 && (
                    <>
                        <section>
                            <h1 className="title-decoration my-10 text-6xl font-roboto-mono-italic text-center text-black">Our Favourites</h1>
                            <section className="recipes">
                                <div className="recipes-grid">
                                    {recipes.length > 0
                                        ? recipes.map(recipe => <HomepageListItem key={recipe.id} recipe={recipe} />)
                                        : <p className="text-2xl text-center">No recipes found.</p>
                                    }
                                </div>
                            </section>
                        </section>
                    </>
                )}
            </div>
        </>
        
    );
}