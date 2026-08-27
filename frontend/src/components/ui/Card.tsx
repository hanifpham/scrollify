import React from 'react';
import { shadowBlock, shadowBlockCompact } from '@/lib/design-tokens';

interface CardProps extends React.HTMLAttributes<HTMLDivElement> {
  compact?: boolean;
}

export const Card: React.FC<CardProps> = ({
  compact = false,
  children,
  className = '',
  style,
  ...props
}) => {
  const baseClasses = 'bg-pure-white border-border-black rounded-sm overflow-hidden';
  const borderClass = compact ? 'border-border-standard' : 'border-border-thick';
  
  return (
    <div
      className={`${baseClasses} ${borderClass} ${className}`}
      style={{
        boxShadow: compact ? shadowBlockCompact : shadowBlock,
        ...style,
      }}
      {...props}
    >
      {children}
    </div>
  );
};
