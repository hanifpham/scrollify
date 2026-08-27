import React, { useState } from 'react';
import { shadowBlock, shadowBlockHover } from '@/lib/design-tokens';

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary';
}

export const Button: React.FC<ButtonProps> = ({
  variant = 'primary',
  children,
  className = '',
  style,
  ...props
}) => {
  const [isPressed, setIsPressed] = useState(false);
  const isPrimary = variant === 'primary';
  
  const baseClasses = `
    inline-flex items-center justify-center font-bold rounded-sm transition-transform duration-75
    disabled:opacity-50 disabled:cursor-not-allowed px-6 py-3
    ${isPressed ? 'translate-x-[2px] translate-y-[2px]' : ''}
  `;
  
  const variantClasses = isPrimary
    ? 'bg-primary border-border-thick border-border-black text-on-primary'
    : 'bg-pure-white border-border-standard border-border-black text-on-surface';

  return (
    <button
      className={`${baseClasses} ${variantClasses} ${className}`}
      style={{
        boxShadow: isPressed ? shadowBlockHover : shadowBlock,
        ...style,
      }}
      onMouseDown={() => setIsPressed(true)}
      onMouseUp={() => setIsPressed(false)}
      onMouseLeave={() => setIsPressed(false)}
      onTouchStart={() => setIsPressed(true)}
      onTouchEnd={() => setIsPressed(false)}
      {...props}
    >
      {children}
    </button>
  );
};
