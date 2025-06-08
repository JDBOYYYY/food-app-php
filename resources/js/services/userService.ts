import apiClient from "./apiClient";
import type { UserDto } from "./types"

export const userService = {
    getProfile: (): Promise<UserDto> => {
        return apiClient<UserDto>("/user");
    },
};
