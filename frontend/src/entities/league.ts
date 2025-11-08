export interface League {
  id: number;
  name: string;
  members: number;
  included?: boolean;
  languages: string[];
}
