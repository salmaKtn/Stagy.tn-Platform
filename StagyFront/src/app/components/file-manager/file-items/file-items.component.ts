import { Component } from '@angular/core';

interface Offer {
  id: number;
  title: string;
  company: string;
  applicants: number;
  status: 'filled' | 'processing' | 'no_applicants';
  comment?: string;
  createdAt: Date;
}

@Component({
  selector: 'app-file-items',
  templateUrl: './file-items.component.html',
  styleUrls: ['./file-items.component.scss']
})
export class FileItemsComponent {
  offers: Offer[] = [
    {
      id: 1,
      title: 'Développeur Full Stack',
      company: 'Société Tunisie Telecom',
      applicants: 5,
      status: 'processing',
      comment: 'En attente de validation RH',
      createdAt: new Date('2023-05-15')
    },
    {
      id: 2,
      title: 'Designer UI/UX',
      company: 'Startup Innovante',
      applicants: 0,
      status: 'no_applicants',
      comment: '',
      createdAt: new Date('2023-06-02')
    },
    {
      id: 3,
      title: 'Ingénieur Data',
      company: 'Entreprise DataSolutions',
      applicants: 3,
      status: 'filled',
      comment: 'Poste pourvu - contrat signé',
      createdAt: new Date('2023-04-20')
    }
  ];

  currentPage = 1;
  itemsPerPage = 10;

  getStatusText(status: string): string {
    const statusMap: Record<string, string> = {
      'filled': 'Poste pourvu',
      'processing': 'En traitement',
      'no_applicants': 'Pas de candidats'
    };
    return statusMap[status] || status;
  }

  get startEntry(): number {
    return (this.currentPage - 1) * this.itemsPerPage + 1;
  }

  get endEntry(): number {
    return Math.min(this.currentPage * this.itemsPerPage, this.totalOffers);
  }

  get totalOffers(): number {
    return this.offers.length;
  }

  get totalPages(): number {
    return Math.ceil(this.totalOffers / this.itemsPerPage);
  }

  changePage(page: number): void {
    if (page >= 1 && page <= this.totalPages) {
      this.currentPage = page;
    }
  }

  editOffer(id: number): void {
    console.log('Modifier offre', id);
    // Implémentez la logique d'édition
  }

  deleteOffer(id: number): void {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) {
      this.offers = this.offers.filter(offer => offer.id !== id);
    }
  }
}