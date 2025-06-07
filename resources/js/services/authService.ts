import apiClient from './apiClient';
import type { RegisterDto, LoginDto, LoginResponseDto } from './types';

export const authService = {
  login: (data: Omit<LoginDto, 'device_name'>): Promise<LoginResponseDto> => {
    return apiClient<LoginResponseDto>('/api/login', {
      method: 'POST',
      body: JSON.stringify({ ...data, device_name: 'webapp' }),
    });
  },
  register: (
    data: Omit<RegisterDto, 'device_name'>,
  ): Promise<LoginResponseDto> => {
    return apiClient<LoginResponseDto>('/api/register', {
      method: 'POST',
      body: JSON.stringify({ ...data, device_name: 'webapp' }),
    });
  },
};