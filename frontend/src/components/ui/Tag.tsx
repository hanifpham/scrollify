import React from 'react';

interface TagProps extends React.HTMLAttributes<HTMLSpanElement> {
  variant: 'new' | 'genre' | 'rating';
}

export const Tag: React.FC<TagProps> = ({
  variant,
  children,
  className = '',
  ...props
}) => {
  const baseClasses = 'inline-flex items-center justify-center border-border-black px-2 py-1 text-label-sm font-bold rounded-none border-border-tag shadow-[4px_4px_0px_0px_#000000]';
  
  let variantClasses = '';
  switch (variant) {
    case 'new':
      variantClasses = 'bg-secondary text-on-secondary uppercase';
      break;
    case 'genre':
      // Using primary as default genre color per specs
      variantClasses = 'bg-primary text-on-primary';
      break;
    case 'rating':
      variantClasses = 'bg-accent-amber text-on-accent-amber';
      break;
  }

  return (
    <span className={`${baseClasses} ${variantClasses} ${className}`} {...props}>
      {children}
    </span>
  );
};
