import { Component, Input, forwardRef } from '@angular/core';
import { ControlValueAccessor, NG_VALUE_ACCESSOR } from '@angular/forms';

@Component({
  selector: 'app-button',
  imports: [],
  templateUrl: './button.html',
  styleUrl: './button.scss',
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => Button),
      multi: true
    }
  ]
})
export class Button implements ControlValueAccessor {
  @Input() label: string = 'button works!';
  @Input() buttonClass: string = '';
  @Input() disabled: boolean = false;

  onChange: any = () => {};
  onTouched: any = () => {};

  writeValue(value: any): void {
    this.disabled = value;
  }

  registerOnChange(fn: any): void {
    this.onChange = fn;
  }

  registerOnTouched(fn: any): void {
    this.onTouched = fn;
  }

  onClick() {
    this.onChange(!this.disabled);
    this.onTouched();
  }

  setDisabledState(isDisabled: boolean): void {
    this.disabled = isDisabled;
  }
}
