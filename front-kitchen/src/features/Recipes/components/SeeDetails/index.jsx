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
 * @description Component to see the details of a recipe
 * 
 */
export default function index({data,recipe}) {
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
          <DialogTitle>Recipe </DialogTitle>
          <DialogDescription>
          {recipe}
          </DialogDescription>
        </DialogHeader>
        <Table>
          <TableCaption>Ingredients list</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead className="w-[100px]">Name</TableHead>
              <TableHead className="text-right">Quantity needed</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {
              data.map( (item) => {
                return (
                  <TableRow>
                    <TableCell className="font-medium">{item.name}</TableCell>
                    <TableCell className="text-right font-bold">{item.pivot.quantity}</TableCell>
                  </TableRow>
                );
              })
              }
          </TableBody>
        </Table>
      </DialogContent>
    </Dialog>
  );
}
