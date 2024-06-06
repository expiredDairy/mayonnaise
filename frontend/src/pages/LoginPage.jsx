
import React from "react";
import LoginForm from "../components/LoginForm";





const LoginPage = ()=>{

return (
    <div className="w-full h-screen bg-[#5c1411cc] flex items-center justify-center  p-10">
      <div className="flex-2 bg-[#E3E3E3] h-full w-fit rounded-2xl p-10 flex flex-col items-center justify-center">
        <h1 className="font-bold text-[#752222] text-3xl self-start">Login</h1>
        <p className="text-sm text-gray-500 self-start mt-2">
          Enter your email and password in order to sign in
        </p>
        <LoginForm />
      </div>
     
    </div>
  );
}

export default LoginPage ;