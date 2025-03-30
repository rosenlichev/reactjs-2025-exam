import parse from 'html-react-parser';
import {useNavigate, useParams} from "react-router-dom";
import {useCreateRecipe, useRecipe} from '../../stores/recipeStore';
import { useState, useActionState, useContext } from "react";

export default function RecipeEdit() {
    const navigate = useNavigate();
    const [message, setMessage] = useState("");
    const [isError, setIsError] = useState(false);
    const {createRecipe} = useCreateRecipe();
    const { id } = useParams();
    const { recipe } = useRecipe(id);

    const handleChange = (event) => {

    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        const formData = new FormData(event.target);

        const values = Object.fromEntries(formData);

        // Manually collect array inputs since FormData does not automatically group "ingredients[]"
        values.ingredients = formData.getAll("ingredients[]");

        const responseData = await createRecipe(values);

        if (responseData.status !== 200) {
            const data = await responseData.json();

            setMessage(data.message);
            setIsError(true);
        } else {
            const data = await responseData.json();
            
            setMessage('');
            setIsError(false);

            if (data.id && data.id > 0) {
                navigate(`/recipe/${data.id}`);
            }
        }
    }

    return (
        <>
            <div className="page-wrapper">
                <section>
                    <h1 className="title-decoration mb-10 text-6xl font-roboto-mono-italic text-center text-black">
                        New Recipe
                    </h1>
                    <section className="auth">
                        <form id="create" onSubmit={handleSubmit}>
                            <input type="hidden" name="id" defaultValue={recipe.id ?? 0} />

                            <div className="flex flex-col gap-2">
                                <label htmlFor="name">Name</label>
                                <input type="text" id="name" name="name" defaultValue={recipe.name ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="preparation_time">Preparation time</label>
                                <input type="text" id="preparation_time" name="preparation_time" defaultValue={recipe.preparationTime ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="servings">Servings</label>
                                <input type="text" id="servings" name="servings" defaultValue={recipe.servings ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="kcal">kCal</label>
                                <input type="text" id="kcal" name="kcal" defaultValue={recipe.kcal ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="carbs">Total carbs</label>
                                <input type="text" id="carbs" name="carbs" defaultValue={recipe.carbs ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="fats">Total fats</label>
                                <input type="text" id="fats" name="fats" defaultValue={recipe.fats ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="proteins">Total proteins</label>
                                <input type="text" id="proteins" name="proteins" defaultValue={recipe.proteins ?? ''} onChange={handleChange} />
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="description">Description</label>
                                <textarea id="description" name="description" defaultValue={recipe.description ?? ''} onChange={handleChange}></textarea>
                            </div>
                            <div className="flex flex-col gap-2">
                                <label htmlFor="preparation">Preparation</label>
                                <textarea id="preparation" name="preparation" defaultValue={recipe.preparation ?? ''} onChange={handleChange}></textarea>
                            </div>
                           {recipe.ingredients && (
                             <div className="flex flex-col gap-2">
                                <label>Ingredients</label>
                                {[...Array(10)].map((_, index) => (
                                    <input
                                        key={index} 
                                        id={`ingredient-${index}`}
                                        type="text" 
                                        name="ingredients[]"
                                        defaultValue={recipe.ingredients[index] ?? ''}
                                    />
                                ))}
                            </div>
                           )}

                            {message !== '' && (
                                <>
                                    {isError === true && (
                                        <div className="p-4 rounded bg-red-300 text-red-900">
                                            {parse(message)}
                                        </div>
                                    )}
                                    {isError === false && (
                                        <div className="p-4 rounded bg-green-300 text-green-900">
                                            {parse(message)}
                                        </div>
                                    )}
                                </>
                            )}

                            <div className="flex flex-col gap-2">
                                <button type="submit">Add Recipe</button>
                            </div>
                        </form>
                    </section>
                </section>
            </div>
        </>
    );
}