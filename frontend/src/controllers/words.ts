import { Injectable } from '@angular/core';
import { Config } from '../config';

@Injectable({
  providedIn: 'root',
})
export class WordsController {
  private config: Config;

  constructor(config: Config) {
    this.config = config;
  }

  async getRandomWords(language: string): Promise<string[]> {
    const url = `https://random-word-api.herokuapp.com/word?number=100&lang=${language}`;

    const response = await fetch(url, {
      method: 'GET',
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message);
    }

    return data as string[];
  }
}
