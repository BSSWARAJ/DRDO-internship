const ros = new ROSLIB.Ros({
    url: 'ws://localhost:9090' // Adjust if necessary
});

const joystick = document.getElementById('joystick-handle');
const joystickContainer = document.getElementById('joystick-container');

let isDragging = false;

joystickContainer.addEventListener('mousedown', (event) => {
    isDragging = true;
    moveJoystick(event);
});

joystickContainer.addEventListener('mousemove', (event) => {
    if (isDragging) {
        moveJoystick(event);
    }
});

joystickContainer.addEventListener('mouseup', () => {
    isDragging = false;
    resetJoystick();
});

joystickContainer.addEventListener('mouseleave', resetJoystick);

function moveJoystick(event) {
    const rect = joystickContainer.getBoundingClientRect();
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    
    const x = event.clientX - rect.left - centerX;
    const y = event.clientY - rect.top - centerY;

    const distance = Math.sqrt(x * x + y * y);
    const maxDistance = centerX; // Radius of the joystick

    if (distance > maxDistance) {
        const angle = Math.atan2(y, x);
        joystick.style.left = `${centerX + Math.cos(angle) * maxDistance - 25}px`; // Adjusted for handle size
        joystick.style.top = `${centerY + Math.sin(angle) * maxDistance - 25}px`;
    } else {
        joystick.style.left = `${centerX + x - 25}px`; // Adjusted for handle size
        joystick.style.top = `${centerY + y - 25}px`;
    }

    const velocity = Math.min(distance / maxDistance, 1); // Normalize to 0-1
    const angle = Math.atan2(y, x);
    
    publishVelocity(velocity, angle);
}

function resetJoystick() {
    joystick.style.left = '50%';
    joystick.style.top = '50%';
    joystick.style.transform = 'translate(-50%, -50%)';
    
    // Stop the TurtleBot
    publishVelocity(0, 0);
}

function publishVelocity(velocity, angle) {
    const cmdVel = new ROSLIB.Topic({
        ros: ros,
        name: '/turtlebot/cmd_vel', // Adjust this topic name based on your setup
        messageType: 'geometry_msgs/Twist'
    });

    const twist = new ROSLIB.Message({
        linear: {
            x: velocity * Math.cos(angle),
            y: velocity * Math.sin(angle),
            z: 0
        },
        angular: {
            x: 0,
            y: 0,
            z: 0
        }
    });

    cmdVel.publish(twist);
    document.getElementById('velocity').innerText = (velocity).toFixed(2);
}

// Set up camera feed
const videoElement = document.getElementById('video');
videoElement.src = 'http://localhost:8080/stream'; // Adjust this to your video server's URL

// Subscribe to actual velocity updates
const cmdVelListener = new ROSLIB.Topic({
    ros: ros,
    name: '/turtlebot/cmd_vel', // Adjust this topic name based on your setup
    messageType: 'geometry_msgs/Twist'
});

cmdVelListener.subscribe((message) => {
    const linearVelocity = Math.sqrt(
        message.linear.x * message.linear.x + message.linear.y * message.linear.y
    ).toFixed(2); // Calculate the magnitude of linear velocity
    document.getElementById('velocity').innerText = linearVelocity; // Update displayed velocity
});

// Subscribe to position updates
const poseListener = new ROSLIB.Topic({
    ros: ros,
    name: '/turtlebot/pose', // Adjust this topic name based on your setup
    messageType: 'geometry_msgs/Pose'
});

poseListener.subscribe((message) => {
    document.getElementById('position').innerText = `${message.position.x.toFixed(2)}, ${message.position.y.toFixed(2)}`;
});
