import React from "react";
import { useNavigate } from "react-router-dom";

export default function index({ path, name, icon }) {
  const location = window.location;

  const isActive = location.pathname === path;
  const navigate = useNavigate(); 

  const goTo = (path) => {
    navigate(path);
  };

  return (
    <a
      className={
        "flex items-center w-full h-9 px-3 mt-2 rounded transition-colors  cursor-pointer" 
      }
      style={{ backgroundColor: isActive ? "#00b19c" : "transparent" , color: isActive ? "white" : ""}}
      onClick={() => goTo(path)}
    >
      {icon}
      <span className="ml-2 text-sm font-medium">{name}</span>
    </a>
  );
}
