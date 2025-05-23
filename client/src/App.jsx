import { BrowserRouter, Routes, Route, useLocation } from 'react-router-dom';
import { useContext } from 'react';
import { UserContext } from './contexts/UserContext';

import UserProvider from './providers/UserProvider'

import AuthGuard from './components/guards/AuthGuard'
import GuestGuard from './components/guards/GuestGuard'

import HeaderHomepage from './components/header/HeaderHomepage'
import Header from './components/header/Header'
import Footer from './components/footer/Footer'
import HomePage from './components/home-page/HomePage'
import RecipesList from './components/recipe-list/RecipeList'
import RecipeDetails from './components/recipe-details/RecipeDetails'
import Login from './components/login/Login';
import Register from './components/register/Register';
import Logout from './components/logout/Logout';
import Dashboard from './components/dashboard/Dashboard';
import MyRecipes from './components/my-recipes/MyRecipes';
import RecipeCreate from './components/recipe-create/RecipeCreate';
import RecipeEdit from './components/recipe-edit/RecipeEdit';
import './App.css'

const HeaderSelector = () => {
  const location = useLocation();
  
  // Check if we're on the homepage (root path)
  const isHomePage = location.pathname === '/';
  
  return isHomePage ? <HeaderHomepage /> : <Header />;
};

function App() {
  return (
    <UserProvider>
      <div id="page">
        <HeaderSelector />

          <div id="site-content">
            <Routes>
              <Route index element={<HomePage />} />
              <Route path="/recipes" element={<RecipesList />} />
              <Route path="/recipe/:id" element={<RecipeDetails />} />

              <Route path="/dashboard" element={<Dashboard />} />

              <Route element={<AuthGuard />}>
                  <Route path="/my-recipes" element={<MyRecipes />} />
                  <Route path="/recipe-create" element={<RecipeCreate />} />
                  <Route path="/recipe-edit/:id" element={<RecipeEdit />} />
                  <Route path="/logout" element={<Logout />} />
              </Route>

              <Route element={<GuestGuard />}>
                  <Route path="/login" element={<Login />} />
                  <Route path="/register" element={<Register />} />
              </Route>
            </Routes>
          </div>

        <Footer />
      </div>
    </UserProvider>
  )
}

export default App
