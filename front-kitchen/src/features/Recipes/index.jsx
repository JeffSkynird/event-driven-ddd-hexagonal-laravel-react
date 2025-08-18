import  {  useState } from "react";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { DataTable } from "@/components/DataTable";
import SeeDetails from "./components/SeeDetails";
import { getRecipes } from "@/service/api";
import {
  useQuery
} from '@tanstack/react-query'
/**
 * @description Component to list recipes
 * 
 */
export default function index() {
  const [currentPage, setCurrentPage] = useState(0);
  const [perPage, setPerPage] = useState(5);
  const { isLoading, data } = useQuery({ queryKey: ['getRecipes',currentPage,perPage], queryFn: getRecipes })

  const columns = [
    {
      accessorKey: "name",
      header: "Name",
    },
    {
      accessorKey: "ingredients_count",
      header: "Number of ingredients",
    },
    {
      accessorKey: "action",
      header: "Details",
      cell: (original) => {
        return (
          <div style={{ display: "flex", justifyContent: "center", gap: "5px" }}>
            <SeeDetails data={original.row.original.ingredients} recipe={original.row.original.name} />
          </div>
        );
      },
    },
  ];
  const { data: recipes = [], total = 0 } = data?.data || {};
  return (
    <Card>
      <CardHeader>
        <CardTitle>Recipes</CardTitle>
        <CardDescription>Recipes available</CardDescription>
      </CardHeader>
      <CardContent>
        <DataTable
          columns={columns}
          data={recipes}
          total={total}
          currentPage={currentPage}
          perPage={perPage}
          setCurrentPage={setCurrentPage}
          setPerPage={setPerPage}
          loading={isLoading}
        />
      </CardContent>
    </Card>
  );
}
