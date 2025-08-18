import "./App.css";
import { ThemeProvider } from "./components/theme-provider";
import { QueryClient, QueryClientProvider,  } from "@tanstack/react-query";
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import Dashboard from "@/features/Dashboard";
import Kitchen from "@/features/Kitchen";
import Orders from "@/features/Orders";
import Ingredients from "@/features/Ingredients";
import Purchases from "@/features/Purchases";
import Recipes from "@/features/Recipes";
import Layout from "./components/layout";
export default function App() {
  const queryClient = new QueryClient({
    defaultOptions: {
      queries: {
        retryDelay: (attemptIndex) => Math.min(1000 * 2 ** attemptIndex, 30000),
      },
    },
  });

  const router = createBrowserRouter([
    {
      path: "/",
      element: <Layout />, 
      errorElement: <div>Not Found</div>,
      children: [
        { path: "/", element: <Dashboard /> },
        { path: "/kitchen", element: <Kitchen /> },
        { path: "/orders", element: <Orders /> },
        { path: "/ingredients", element: <Ingredients /> },
        { path: "/purchases", element: <Purchases /> },
        { path: "/recipes", element: <Recipes /> },
      ],
    },
  ]);

  return (
    <ThemeProvider defaultTheme="light" storageKey="vite-ui-theme">
      <QueryClientProvider client={queryClient}>
        <RouterProvider router={router} />
      </QueryClientProvider>
    </ThemeProvider>
  );
}