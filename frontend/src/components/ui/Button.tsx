import React from 'react';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary';
}

export const Button: React.FC<ButtonProps> = ({
  variant = 'primary',
  children,
  className = '',
  disabled,
  ...props
}) => {
  const isPrimary = variant === 'primary';
  
  const hoverClasses = disabled 
    ? '' 
    : 'hover:shadow-[4px_4px_0px_0px_#000000] hover:-translate-x-[2px] hover:-translate-y-[2px]';

  const activeClasses = disabled
    ? ''
    : 'active:shadow-[2px_2px_0px_0px_#000000] active:translate-x-[2px] active:translate-y-[2px] active:scale-[0.98] active:duration-75';

  const baseClasses = `
    inline-flex items-center justify-center font-bold rounded-none transition-all duration-150
    disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3
    shadow-[4px_4px_0px_0px_#000000] translate-x-0 translate-y-0
    ${hoverClasses} ${activeClasses}
  `;
  
  const variantClasses = isPrimary
    ? 'bg-primary border-border-thick border-border-black text-on-primary'
    : 'bg-pure-white border-border-thick border-border-black text-on-surface';

  return (
    <button
      className={`${baseClasses} ${variantClasses} ${className}`}
      disabled={disabled}
      {...props}
    >
      {children}
    </button>
  );
};
