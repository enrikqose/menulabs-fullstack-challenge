import type {CurrentWeather, UserOverview} from "@/modules/user/types";
import type { User } from "@/models/user";

export interface UsersResponse {
  cursor: string | null;
  users: UserOverview[];
}

export interface WeatherOverview {
  userId: number;
  icon: string;
  status: string;
  temp: number;
}

export async function getUsers(
  cursor: string | null = null
): Promise<UsersResponse> {
  try {
    let url = `${import.meta.env.APP_API_BASE_URL}/users`;

    if (cursor) {
      url += `?cursor=${cursor}`;
    }

    const response = await fetch(url, {
      headers: {
        "Content-Type": "application/json",
      },
    });

    const result = await response.json();
    const data: UsersResponse = { cursor: result.next_cursor, users: [] };

    data.users = result.data.map((user: User): UserOverview => {
      return {
        id: user.id,
        fullName: user.name,
        status: "Clear",
        statusIcon: "clear-day",
        temperature: 0,
        loading: true,
      };
    });

    return data;
  } catch (error) {
    console.error("Error while retrieving users", error);
    return {
      cursor: null,
      users: [],
    };
  }
}

export async function getUsersWeatherOverview(
  users: number[]
): Promise<WeatherOverview[]> {
  try {
    let url = `${
      import.meta.env.APP_API_BASE_URL
    }/users/current-weather?`;
    url += new URLSearchParams({ users: users.join(",") });

    const response = await fetch(url, {
      headers: {
        "Content-Type": "application/json",
      },
    });

    return (await response.json()) as WeatherOverview[];
  } catch (error) {
    console.error("Error while retrieving users weather overview", error);
    return [];
  }
}

export async function getCurrentWeather(
  userId: number
): Promise<CurrentWeather | null> {
  try {
    const url = `${
      import.meta.env.APP_API_BASE_URL
    }/users/${userId}/current-weather`;

    const response = await fetch(url, {
      headers: {
        "Content-Type": "application/json",
      },
    });

    return (await response.json()) as CurrentWeather;
  } catch (error) {
    console.error("Error while retrieving user's current weather", error);
    return null;
  }
}
