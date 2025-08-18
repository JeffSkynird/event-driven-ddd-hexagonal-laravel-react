import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import { FaEye } from "react-icons/fa";
import { Button } from "@/components/ui/button";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
  } from "@/components/ui/table"
/**
 * @description Component to see the details of an purchase
 *
 */
export default function index() {
  return (
    <Dialog>
      <DialogTrigger>
        {" "}
        <Button variant="customGreen" size="icon">
          <FaEye />
        </Button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Order #1 - Pending</DialogTitle>
          <DialogDescription>
            Dish requested: Pasta
          </DialogDescription>
        </DialogHeader>
        <Table>
          <TableCaption>Ingredients list</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead className="w-[100px]">Name</TableHead>
              <TableHead className="text-right">Amount</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow>
              <TableCell className="font-medium">INV001</TableCell>
              <TableCell className="text-right">10</TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </DialogContent>
    </Dialog>
  );
}
