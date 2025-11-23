export enum EIdioma {
  INGLES = "en",
  ALEMAO = "de",
  ITALIANO = "it",
  PORTUGUES = "pt-br",
  CHINES = "zh",
  ESPANHOL = "es",
  FRANCES = "fr"
}

export interface IIdiomaDetalhe {
  id: EIdioma;
  title: string;
  countryCode: string;
}

export const ListaIdiomas: IIdiomaDetalhe[] = [
  {
    id: EIdioma.INGLES,
    title: 'Inglês ',
    countryCode: 'US'
  },
  {
    id: EIdioma.ALEMAO,
    title: 'Alemão',
    countryCode: 'DE'
  },
  {
    id: EIdioma.ITALIANO,
    title: 'Italiano',
    countryCode: 'IT'
  },
  {
    id: EIdioma.PORTUGUES,
    title: 'Português (Brasil)',
    countryCode: 'BR'
  },
  {
    id: EIdioma.CHINES,
    title: 'Chinês',
    countryCode: 'CN'
  },
  {
    id: EIdioma.ESPANHOL,
    title: 'Espanhol',
    countryCode: 'ES'
  },
  {
    id: EIdioma.FRANCES,
    title: 'Francês',
    countryCode: 'FR'
  },
];
