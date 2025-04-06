import { ApiController } from "/scripts/libs/apiController.js";
import { getUserData } from "/scripts/libs/getUserData.js";

const apiController = new ApiController();
const user_id = getUserData().id;

// TODO: complete and build message
submitPrompt.addEventListener('click', async () => {
    const prompt = promptInput.value;

    const res = await apiController.promtToAI(user_id, prompt);
    console.log(res);
});