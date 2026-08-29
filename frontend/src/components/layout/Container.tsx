import React from 'react';

interface ContainerProps extends React.HTMLAttributes<HTMLDivElement> {}

export const Container: React.FC<ContainerProps> = ({
  children,
  className = '',
  ...props
}) => {
  return (
    <div className={`max-w-360 mx-auto p-6 w-full ${className}`} {...props}>
      {children}
    </div>
  );
};
