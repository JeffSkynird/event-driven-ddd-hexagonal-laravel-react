import  { useState } from "react";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { DataTable } from "@/components/DataTable";
import SeeDetails from "./components/SeeDetails";
import { getIngredients } from "@/service/api";
import { useQuery } from "@tanstack/react-query";

/**
 * @description Component for the Ingredients page
 * 
 */
export default function index() {
  const [currentPage, setCurrentPage] = useState(0);
  const [perPage, setPerPage] = useState(5); 
  const {data ,isLoading} = useQuery({
    queryKey: ["getIngredients", currentPage, perPage],
    queryFn: getIngredients,
  });

  const columns = [
    {
      accessorKey: "name",
      header: "Name",
    },
    {
      accessorKey: "available_quantity",
      header: "Stock",
    },

    {
      accessorKey: "action",
      header: "Details",
      cell: (original) => {
        return (
          <div
            style={{ display: "flex", justifyContent: "center", gap: "5px" }}
          >
            <SeeDetails
              data={original.row.original.inventory_movements}
              ingredient={original.row.original.name}
              stock={original.row.original.available_quantity}
            />
          </div>
        );
      },
    },
  ];
  const { data: ingredients = [], total = 0 } = data?.data || {};

  return (
    <Card>
      <CardHeader>
        <CardTitle>Warehouse</CardTitle>
        <CardDescription>Ingredients available</CardDescription>
      </CardHeader>
      <CardContent>
        <DataTable
          columns={columns}
          data={ingredients}
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
