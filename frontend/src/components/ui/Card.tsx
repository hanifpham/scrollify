import React from 'react';

interface CardProps extends React.HTMLAttributes<HTMLDivElement> {
  compact?: boolean;
  interactive?: boolean;
}

export const Card: React.FC<CardProps> = ({
  compact = false,
  interactive = false,
  children,
  className = '',
  style,
  ...props
}) => {
  const borderClass = compact ? 'border-border-standard' : 'border-border-thick';
  
  const shadowBase = compact ? 'shadow-[4px_4px_0px_0px_#000000]' : 'shadow-[10px_10px_0px_0px_#000000]';
  const hoverShadow = compact ? 'hover:shadow-[4px_4px_0px_0px_#000000]' : 'hover:shadow-[10px_10px_0px_0px_#000000]';
  const activeShadow = compact ? 'active:shadow-[2px_2px_0px_0px_#000000]' : 'active:shadow-[6px_6px_0px_0px_#000000]';

  const hoverClasses = interactive 
    ? `${hoverShadow} hover:-translate-x-[2px] hover:-translate-y-[2px] ${activeShadow} active:translate-x-[2px] active:translate-y-[2px] active:scale-[0.98] active:duration-75 transition-all duration-150 cursor-pointer` 
    : '';

  const baseClasses = `bg-pure-white border-border-black rounded-none overflow-hidden translate-x-0 translate-y-0 ${shadowBase} ${hoverClasses}`;
  
  return (
    <div
      className={`${baseClasses} ${borderClass} ${className}`}
      style={style}
      {...props}
    >
      {children}
    </div>
  );
};
