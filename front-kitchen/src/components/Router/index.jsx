import { createBrowserRouter, RouterProvider } from "react-router-dom";
import Dashboard from "@/features/Dashboard";
import Kitchen from "@/features/Kitchen";
import Orders from "@/features/Orders";
import Ingredients from "@/features/Ingredients";
import Purchases from "@/features/Purchases";
import Recipes from "@/features/Recipes";
export default function index() {
  const router = createBrowserRouter([
    {
      path: "/",
      element: <Dashboard />,
      errorElement: <div>Not Found</div>,
    },
    {
      path: "/kitchen",
      element: <Kitchen />,
    },
    {
        path: "/orders",
        element: <Orders />,
      },
      {
        path: "/ingredients",
        element: <Ingredients />,
      },
      {
        path: "/purchases",
        element: <Purchases />,
      },
      {
        path: "/recipes",
        element: <Recipes />,
      }
  ]);
  return <RouterProvider router={router} />;
}
