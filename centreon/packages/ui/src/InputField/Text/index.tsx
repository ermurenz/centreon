import { forwardRef } from 'react';

import { isNil } from 'ramda';
import { makeStyles } from 'tss-react/mui';

import {
  TextField as MuiTextField,
  InputAdornment,
  TextFieldProps,
  Theme,
  Tooltip,
  Typography
} from '@mui/material';

import getNormalizedId from '../../utils/getNormalizedId';

import useAutoSize from './useAutoSize';

const useStyles = makeStyles<{ autoSize: boolean }>()(
  (theme: Theme, { autoSize }) => ({
    compact: {
      fontSize: 'x-small'
    },
    hiddenText: {
      display: 'table',
      transform: 'scaleY(0)'
    },
    input: {
      fontSize: theme.typography.body1.fontSize
    },
    inputBase: {
      display: autoSize ? 'block' : 'inline-flex',
      paddingRight: theme.spacing(1)
    },
    noLabelInput: {
      padding: theme.spacing(1)
    },
    textField: {
      transition: theme.transitions.create(['width'], {
        duration: theme.transitions.duration.shortest
      })
    },
    transparent: {
      backgroundColor: 'transparent'
    }
  })
);

interface OptionalLabelInputAdornmentProps {
  children: React.ReactNode;
  label?: React.ReactNode;
  position: 'end' | 'start';
}

const OptionalLabelInputAdornment = ({
  label,
  position,
  children
}: OptionalLabelInputAdornmentProps): JSX.Element => {
  const { classes } = useStyles({ autoSize: false });
  const noMarginWhenNoLabel = !label && { style: { marginTop: 0 } };

  return (
    <InputAdornment
      {...noMarginWhenNoLabel}
      className={classes.endAdornment}
      position={position}
    >
      {children}
    </InputAdornment>
  );
};

export type Props = {
  EndAdornment?: React.FC;
  StartAdornment?: React.FC;
  ariaLabel?: string;
  autoSize?: boolean;
  autoSizeCustomPadding?: number;
  autoSizeDefaultWidth?: number;
  className?: string;
  dataTestId: string;
  displayErrorInTooltip?: boolean;
  error?: string;
  externalValueForAutoSize?: string;
  open?: boolean;
  size?: 'large' | 'medium' | 'small' | 'compact';
  transparent?: boolean;
  value?: string;
} & Omit<TextFieldProps, 'variant' | 'size' | 'error'>;

const TextField = forwardRef(
  (
    {
      StartAdornment,
      EndAdornment,
      label,
      error,
      ariaLabel,
      dataTestId,
      transparent = false,
      size,
      displayErrorInTooltip = false,
      className,
      autoSize = false,
      autoSizeDefaultWidth = 0,
      externalValueForAutoSize,
      autoSizeCustomPadding,
      ...rest
    }: Props,
    ref: React.ForwardedRef<HTMLDivElement>
  ): JSX.Element => {
    const { classes, cx } = useStyles({ autoSize });

    const { inputRef, width, changeInputValue, innerValue } = useAutoSize({
      autoSize,
      autoSizeCustomPadding,
      autoSizeDefaultWidth,
      value: externalValueForAutoSize || rest.value
    });

    const tooltipTitle = displayErrorInTooltip && !isNil(error) ? error : '';

    return (
      <>
        <Tooltip placement="top" title={tooltipTitle}>
          <MuiTextField
            data-testid={dataTestId}
            error={!isNil(error)}
            helperText={displayErrorInTooltip ? undefined : error}
            id={getNormalizedId(dataTestId || '')}
            inputProps={{
              ...rest.inputProps,
              'aria-label': ariaLabel,
              'data-testid': dataTestId
            }}
            label={label}
            ref={ref}
            size={size || 'small'}
            value={innerValue}
            onChange={changeInputValue}
            {...rest}
            InputProps={{
              className: cx(
                classes.inputBase,
                {
                  [classes.transparent]: transparent
                },
                className
              ),
              disableUnderline: true,
              endAdornment: (
                <OptionalLabelInputAdornment label={label} position="end">
                  {EndAdornment ? (
                    <EndAdornment />
                  ) : (
                    rest.InputProps?.endAdornment
                  )}
                </OptionalLabelInputAdornment>
              ),
              startAdornment: StartAdornment && (
                <OptionalLabelInputAdornment label={label} position="start">
                  <StartAdornment />
                </OptionalLabelInputAdornment>
              ),
              ...rest.InputProps
            }}
            className={classes.textField}
            sx={{
              width: autoSize ? width : undefined,
              ...rest?.sx
            }}
          />
        </Tooltip>
        <Typography className={classes.hiddenText} ref={inputRef}>
          {rest.value || externalValueForAutoSize || innerValue}
        </Typography>
      </>
    );
  }
);

export default TextField;
