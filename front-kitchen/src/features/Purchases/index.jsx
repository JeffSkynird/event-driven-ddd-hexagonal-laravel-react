import {  useState } from "react";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { DataTable } from "@/components/DataTable";
import { getPurchases } from "@/service/api";
import { useQuery } from "@tanstack/react-query";
import { Badge } from "@/components/ui/badge";

/**
 * @description Component to list purchases
 * 
 */
export default function index() {
  const [currentPage, setCurrentPage] = useState(0); 
  const [perPage, setPerPage] = useState(5);
  const { isLoading, data } = useQuery({ queryKey: ['getPurchases',currentPage,perPage], queryFn: getPurchases })

  const columns = [
    {
      accessorKey: "status",
      header: "Status",
      cell: (original) => {
        return (
          <Badge variant="outline">
            <span
              className={` ${
                  original.row.original.status === "completed"
                  ? "text-green-500"
                  : "text-red-500"
              }`}
            >
              {original.row.original.status}
            </span>
          </Badge>
        );
      },
    },
    {
      accessorKey: "ingredient_name",
      header: "Ingredient",
    },
    {
      accessorKey: "quantity_requested",
      header: "Quantity requested",
    },
    {
      accessorKey: "quantity_purchased",
      header: "Quantity purchased",
    },
    {
      accessorKey: "created_at",
      header: "Date",
      cell: (original) => (
        new Date(formatDate(original.row.original.created_at)).toLocaleString()
      ),
    }
  ];
  const formatDate = (dateString) => {
    if(!dateString) {
      return '';
    }
    if (dateString.includes('T') && dateString.includes('Z')) {
      return dateString; 
    }
    let normalizedDate = dateString.replace(' ', 'T');
  
    if (!normalizedDate.includes('.')) {
      normalizedDate += '.000000Z'; 
    }
  
    return normalizedDate;
  };

  const { data: ingredients = [], total = 0 } = data?.data || {};
  return (
    <Card>
      <CardHeader>
        <CardTitle>Purchases</CardTitle>
        <CardDescription>History of purchases (food court)</CardDescription>
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
