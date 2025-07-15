export const getLocalDtFormat = (dateTime) => {
  if (!dateTime) return '';

  let locale = window.navigator.language;
  let date = new Date(dateTime);

  return new Intl.DateTimeFormat(locale, {
    dateStyle: 'medium',
    timeStyle: 'short'
  }).format(date);
};

export const getTimezone = () => {
  return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

export const generateNumberSuffix = (date) => {
	if (date.slice(-1) == "1" && date.slice(-2) != "11") return date + "st";
	else if (date.slice(-1) == "2" && date.slice(-2) != "12") return date + "nd";
	else if (date.slice(-1) == "3" && date.slice(-2) != "13") return date + "rd";
	else return date + "th";
};
