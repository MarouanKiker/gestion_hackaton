"use strict";

const participantForm = document.getElementById("participantForm");
const participantList = document.getElementById("participantList");
const teamSelect = document.getElementById("idEquipe");

function fetchTeams() {
fetch("../php/api/get-teams.php")
    .then((response) => response.json())
    .then((teams) => {
      teamSelect.innerHTML = "";
      teams.forEach((team) => {
        const option = document.createElement("option");
        option.value = team.IdEquipe;
        option.textContent = team.nom;
        teamSelect.appendChild(option);
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des équipes :", error);
    });
}

function fetchParticipants() {
fetch("../php/api/participant.php?action=list")
    .then((response) => response.json())
    .then((data) => {
      participantList.innerHTML = "";
      data.forEach((participant) => {
        const li = document.createElement("li");
        li.textContent = `${participant.nom} ${participant.prenom} - ${participant.email}`;
        const deleteButton = document.createElement("button");
        deleteButton.textContent = "Supprimer";
        deleteButton.addEventListener("click", () => {
          deleteParticipant(participant.IdParticipant);
        });
        li.appendChild(deleteButton);
        participantList.appendChild(li);
      });
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des participants :", error);
    });
}

function deleteParticipant(id) {
fetch(`../php/api/participant.php?action=delete&id=${id}`, {
    method: "DELETE",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        fetchParticipants();
      } else {
        alert("Erreur lors de la suppression du participant : " + (data.error || data.message));
      }
    })
    .catch((error) => {
      alert("Erreur lors de la suppression du participant : " + error);
    });
}

participantForm.addEventListener("submit", (event) => {
  event.preventDefault();

  const formData = new FormData(participantForm);

fetch("../php/api/participant.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert(data.message);
        participantForm.reset();
        fetchParticipants();
      } else {
        alert("Erreur lors de la création du participant : " + (data.error || data.message));
      }
    })
    .catch((error) => {
      alert("Erreur lors de la création du participant : " + error);
    });
});

fetchTeams();
fetchParticipants();
