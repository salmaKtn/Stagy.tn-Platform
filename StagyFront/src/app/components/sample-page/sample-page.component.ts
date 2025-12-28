import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { BreadcrumbComponent } from '../../shared/components/breadcrumb/breadcrumb.component';
import { CommonModule } from '@angular/common';

interface DemandeEntreprise {
  id: number;
  nom: string;
  email: string;
  tva: string;
}

@Component({
  selector: 'app-sample-page',
  templateUrl: './sample-page.component.html',
  styleUrls: ['./sample-page.component.scss'],
  imports: [BreadcrumbComponent, CommonModule],
})
export class SamplePageComponent implements OnInit {
 

  demandes: DemandeEntreprise[] = [
    { id: 1, nom: 'Entreprise Alpha', email: 'contact@alpha.com', tva: 'FR123456789' },
    { id: 2, nom: 'Beta SAS', email: 'contact@beta.fr', tva: 'FR987654321' },
    { id: 3, nom: 'Gamma & Co', email: 'info@gamma.co', tva: 'FR456789123' },
    { id: 4, nom: 'Delta Corp', email: 'support@delta.com', tva: 'FR654321987' },
    { id: 5, nom: 'Epsilon SARL', email: 'hello@epsilon.fr', tva: 'FR321987654' },
    { id: 6, nom: 'Zeta Industries', email: 'contact@zeta.com', tva: 'FR789123456' },
  ];

  selectedDemande: DemandeEntreprise | null = null;
  actionType: 'approuver' | 'rejeter' | null = null;

  @ViewChild('confirmationModal') modal!: ElementRef;

  constructor() { }

  ngOnInit(): void { }

  ouvrirModal(demande: DemandeEntreprise, action: 'approuver' | 'rejeter') {
    this.selectedDemande = demande;
    this.actionType = action;
    const modalEl = new (window as any).bootstrap.Modal(this.modal.nativeElement);
    modalEl.show();
  }

  fermerModal() {
    const modalEl = (window as any).bootstrap.Modal.getInstance(this.modal.nativeElement);
    modalEl?.hide();
  }

  confirmerAction() {
    if (this.selectedDemande && this.actionType) {
      // TODO: replace alert with your backend call
      alert(`Vous avez ${this.actionType} la demande de ${this.selectedDemande.nom}`);
      this.fermerModal();
    }
  }
}
