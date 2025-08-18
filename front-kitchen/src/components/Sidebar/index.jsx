import { useState } from "react";
import MenuItem from "@/components/Menutem";
import logo from "@/assets/logo.png";
import { RiHome3Line } from "react-icons/ri";
import { TbToolsKitchen2 } from "react-icons/tb";
import { HiOutlineDocumentText } from "react-icons/hi";
import { HiOutlineDocumentDuplicate } from "react-icons/hi";
import { MdOutlineShoppingCart } from "react-icons/md";
import { RiShoppingBasketLine } from "react-icons/ri";
import { useTheme } from "../theme-provider";
export default function Sidebar() {
  const [isOpen, setIsOpen] = useState(false);

  const { theme } = useTheme()
  console.log(theme)
  const toggleSidebar = () => {
    setIsOpen(!isOpen);
  };
  const menuItems = [
    {
      name: "Home",
      path: "/",
      icon: (
        <RiHome3Line className="w-5 h-5 stroke-current" />
      ),
    },
    {
      name: "Kitchen",
      path: "/kitchen",
      icon: (
        <TbToolsKitchen2 className="w-5 h-5 stroke-current" />
      ),
    },
    {
      name: "Orders",
      path: "/orders",
      icon: (
        <HiOutlineDocumentText className="w-5 h-5 stroke-current" />
      ),
    },
    {
      name: "Ingredients",
      path: "/ingredients",
      icon: (
        <HiOutlineDocumentDuplicate className="w-5 h-5 stroke-current" />
      ),
    },
    {
      name: "Purchases",
      path: "/purchases",
      icon: (
        <MdOutlineShoppingCart className="w-5 h-5 stroke-current" />
      ),
    },
    {
      name: "Recipes",
      path: "/recipes",
      icon: (
        <RiShoppingBasketLine className="w-5 h-5 stroke-current" />
      ),
    },
  ];
  return (
    <>
      <button
        className={
          "fixed top-4 left-4 z-20 block md:hidden bg-gray-700 text-white p-2 rounded-md focus:outline-none opacity-80  hover:opacity-100 " +
          (isOpen ? "hidden" : "block")
        }
        onClick={toggleSidebar}
      >
        <svg
          className="w-4 h-4"
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M4 6h16M4 12h16m-7 6h7"
          />
        </svg>
      </button>

      <div
        className={`sm:bg-current fixed inset-y-0 left-0 z-10 w-48  transition-transform transform md:translate-x-0 ${
          isOpen ? "translate-x-0" : "-translate-x-full"
        } md:static md:inset-auto md:w-48 md:h-full md:overflow-hidden md:flex md:flex-col md:items-center border-r `}
        style={{ backgroundColor: theme === "dark" ? "#020617" : "white" }}
      >

        <button
          className="md:hidden text-gray-600 absolute top-4 right-4 focus:outline-none"
          onClick={toggleSidebar}
        >
          <svg
            className="w-6 h-6"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={2}
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
        <a className="flex items-center w-full px-3 mt-3" href="/">
          <img src={logo} alt="logo" className="w-7 h-7" />
          <span
            className={
              "ml-2 text-xs font-bold xs:hidden " +
              (isOpen ? "hidden" : "block transition-opacity ")
            }
          >
            Proyecto
          </span>
        </a>
        <div className="w-full px-2">
          {menuItems.map((item, i) =>
            i === 0 ? (
              <div
                key={item.name}
                className="flex flex-col items-center w-full mt-2 border-t "
              >
                <MenuItem name={item.name} path={item.path} icon={item.icon} />
              </div>
            ) : (
              <MenuItem
                key={item.name}
                name={item.name}
                path={item.path}
                icon={item.icon}
              />
            )
          )}
        </div>
      </div>
    </>
  );
}
