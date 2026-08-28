import React from 'react';

export interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {}

export const Input: React.FC<InputProps> = ({ className = '', ...props }) => {
  return (
    <input
      className={`
        bg-pure-white border-border-standard border-border-black rounded-sm
        px-4 py-2 font-bold text-on-surface placeholder:text-on-surface-variant placeholder:font-bold
        focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20
        transition-colors
        ${className}
      `}
      {...props}
    />
  );
};
