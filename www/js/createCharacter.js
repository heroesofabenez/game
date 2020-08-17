function changeRaceDescription(race) {
    const raceDiv = document.getElementById("raceDescription");
    raceDiv.innerHTML = racesDes[race];
}
function changeClassDescription(classID) {
    const classDiv = document.getElementById("classDescription");
    classDiv.innerHTML = classesDes[classID];
}
