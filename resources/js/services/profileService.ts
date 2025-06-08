import apiClient from "./apiClient";
import type { UserDto } from "./types";

interface UpdateProfileDto {
    name: string;
    email: string;
}

interface ChangePasswordDto {
    current_password: string;
    new_password: string;
    new_password_confirmation: string;
}

export const profileService = {
    updateProfile: (data: UpdateProfileDto): Promise<UserDto> => {
        return apiClient<UserDto>("/api/user/profile", {
            method: "PUT",
            body: JSON.stringify(data),
        });
    },

    changePassword: (data: ChangePasswordDto): Promise<{ message: string }> => {
        return apiClient<{ message: string }>("/api/user/change-password", {
            method: "POST",
            body: JSON.stringify(data),
        });
    },
};
