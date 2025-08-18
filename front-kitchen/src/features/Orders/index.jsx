import  {  useState } from "react";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import SeeDetails from "./components/SeeDetails";
import { Button } from "@/components/ui/button";
import { MdRefresh } from "react-icons/md";
import { DataTable } from "@/components/DataTable";
import { getOrdersPaginated } from "@/service/api";
import { Badge } from "@/components/ui/badge";
import { useToast } from "@/hooks/use-toast";
import { prepareOrder } from "@/service/api";
import { ToastAction } from "@radix-ui/react-toast";
import { useQuery } from "@tanstack/react-query";

/**
 * @description Component to list orders
 * 
 */
export default function Orders() {
  const { toast } = useToast()
  const [currentPage, setCurrentPage] = useState(0); 
  const [perPage, setPerPage] = useState(5); 
  const { data,isLoading} = useQuery({ queryKey: ['getOrdersPaginated',currentPage,perPage], queryFn: getOrdersPaginated })

  const columns = [
    {
      accessorKey: "id",
      header: "Order #",
    },
    {
      accessorKey: "order_status",
      header: "Status",
      cell: (original) => {
        return (
          <Badge variant="outline">
            <span
              className={` ${
                original.row.original.order_status === "pending" ||
                original.row.original.order_status === "restocking" || original.row.original.order_status === "preparing"
                  ? "text-yellow-500"
                  : original.row.original.order_status === "completed"
                  ? "text-green-500"
                  : "text-red-500"
              }`}
            >
              {original.row.original.order_status}
            </span>
          </Badge>
        );
      },
    },
    {
      accessorKey: "dish_id",
      header: "Dish",
      cell: (original) => {
        return <span>{original.row.original?.dish?.name}</span>;
      },
    },
    {
      accessorKey: "created_at",
      header: "Date",
      cell: (original) => {
        return (
          <span>
            {new Date(original.row.original.created_at).toLocaleString()}
          </span>
        );
      },
    },
    {
      accessorKey: "action",
      header: "Details",
      cell: (original) => {
        return (
          <div style={{ display: "flex", justifyContent: "center", gap: "5px" }}>
            <SeeDetails data={original.row.original.dish.ingredients} orderStatus={original.row.original.order_status} dishName={original.row.original.dish.name} />
            {original.row.original.order_status === "error" && (
              <Button size="icon" variant="destructive" onClick={() => retry(original.row.original.id)}>
                <MdRefresh className="h-5 w-5" />
              </Button>
            )}
          </div>
        );
      },
    },
  ];
  const retry = async (orderId) => {
    try{
      const data = await prepareOrder(orderId);
      toast({
        title: 'Retrying order',
        description: data.message,
        action: (
          <ToastAction altText="Goto orders" onClick={()=> window.location.href = "/orders"}>See orders</ToastAction>
        ),
      })
    }catch(e){
      toast({
        title: 'Error',
        description: e.message
   
      })
    }
  };
  const { data: orders = [], total = 0 } = data?.data || {};
  return (
    <Card>
      <CardHeader>
        <CardTitle>Request order</CardTitle>
        <CardDescription>Get a random dish</CardDescription>
      </CardHeader>
      <CardContent>
        <DataTable
          columns={columns}
          data={orders}
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
