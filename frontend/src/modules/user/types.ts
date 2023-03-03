export interface UserOverview {
  id: number;
  fullName: string;
  temperature: number;
  statusIcon: string;
  status: string;
  loading: boolean;
}

export interface CurrentWeather {
  status: string;
  description: string;
  icon: string;
  temp: number;
  feelsLike: number;
  tempMin: number;
  tempMax: number;
  pressure: number;
  humidity: number;
  windSpeed: number;
  windDir: number;
  visibility: number;
  clouds: number;
}
